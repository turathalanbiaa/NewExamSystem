import Page1 from "./components/Page1";
import Page2 from "./components/Page2";
export const routes= [

    {
        path: '/',
        redirect:{
            name:"Page1"
        }
    },
    {
        path: '/Page1',
        name: 'Page1',
        component: Page1
    },
    {
        path: '/page2',
        name: 'Page2',
        component: Page2
    },
];