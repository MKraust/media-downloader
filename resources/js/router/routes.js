import Tracker from '../components/pages/Tracker'
import Media from '../components/pages/Media'

export default [
    {
        name: 'tracker',
        path: '/:trackerId',
        component: Tracker,
    },
    {
        name: 'media',
        path: '/:trackerId/:mediaId',
        component: Media,
    }
]
