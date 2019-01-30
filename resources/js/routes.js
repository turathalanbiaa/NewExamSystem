import Exams from "./components/Exams";
import Questions from "./components/Questions";

export const routes= [
    {
        path: '/',
        components: {
            Exams
        }

    },
    {
        path: '/Questions/:id',
        component: Questions,
    },

];