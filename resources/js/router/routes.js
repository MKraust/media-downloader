import Tracker from '../components/Tracker'
import Media from '../components/Media'

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
