import moment, { MomentInput } from 'moment'
import 'moment/locale/ru'

moment.locale('ru')

const getMomentDate = (date: MomentInput) => {
  return moment(date)
}

export const humanizeDatetime = (date: MomentInput) => {
  return getMomentDate(date).format('lll')
}
