import React, { FC, createContext, useContext, useEffect, useState, ReactNode, PropsWithChildren } from 'react'

import { PropsWithOnlyChildren } from '@/helpers'

export interface PageLink {
  title: string
  path: string
  isActive: boolean
  isSeparator?: boolean
}

export interface PageDataContextModel {
  pageTitle?: ReactNode
  setPageTitle: (_title: ReactNode) => void
  pageDescription?: string
  setPageDescription: (_description: string) => void
  pageBreadcrumbs?: Array<PageLink>
  setPageBreadcrumbs: (_breadcrumbs: Array<PageLink>) => void
}

const PageDataContext = createContext<PageDataContextModel>({
  setPageTitle: (_title: ReactNode) => {},
  setPageBreadcrumbs: (_breadcrumbs: Array<PageLink>) => {},
  setPageDescription: (_description: string) => {},
})

const PageDataProvider: React.FC<PropsWithOnlyChildren> = ({ children }) => {
  const [pageTitle, setPageTitle] = useState<ReactNode>('')
  const [pageDescription, setPageDescription] = useState<string>('')
  const [pageBreadcrumbs, setPageBreadcrumbs] = useState<Array<PageLink>>([])
  const value: PageDataContextModel = {
    pageTitle,
    setPageTitle,
    pageDescription,
    setPageDescription,
    pageBreadcrumbs,
    setPageBreadcrumbs,
  }
  return <PageDataContext.Provider value={value}>{children}</PageDataContext.Provider>
}

function usePageData() {
  return useContext(PageDataContext)
}

type Props = {
  description?: string
  breadcrumbs?: Array<PageLink>
}

const PageTitle: FC<PropsWithChildren<Props>> = ({ children, description, breadcrumbs }) => {
  const { setPageTitle, setPageDescription, setPageBreadcrumbs } = usePageData()
  useEffect(() => {
    if (children) {
      setPageTitle(children)
    }
    return () => {
      setPageTitle('')
    }
  }, [children])

  useEffect(() => {
    if (description) {
      setPageDescription(description)
    }
    return () => {
      setPageDescription('')
    }
  }, [description])

  useEffect(() => {
    if (breadcrumbs) {
      setPageBreadcrumbs(breadcrumbs)
    }
    return () => {
      setPageBreadcrumbs([])
    }
  }, [breadcrumbs])

  return <></>
}

const PageDescription: React.FC<PropsWithOnlyChildren> = ({ children }) => {
  const { setPageDescription } = usePageData()
  useEffect(() => {
    if (children) {
      setPageDescription(children.toString())
    }
    return () => {
      setPageDescription('')
    }
  }, [children])
  return <></>
}

export { PageDescription, PageTitle, PageDataProvider, usePageData }
