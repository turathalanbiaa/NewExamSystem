import Page1 from "./components/Page1";
import Page2 from "./components/Page2";

export const routes= [
    {
        path: '/',
        components: {
          Page1
        }

    },
    {
        path: '/page2/:id',
        component: Page2,
    },

];