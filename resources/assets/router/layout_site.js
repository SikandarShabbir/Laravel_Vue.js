const layout = [

    {
        path: '/locations',
        component: resolve => require(['site/locations'], resolve),
        meta: {
            title: "Locations",
            forSite: true,
        }
    },
]

export default layout