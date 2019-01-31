import Exams from "./components/Exams";
import Exam from "./components/Exam";

export const routes= [
    {
        path: '/',
        components: {
            Exams
        }

    },
    {
        path: '/Exam/:id',
        component: Exam,
    },

];