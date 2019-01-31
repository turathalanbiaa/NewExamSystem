<template>
    <v-container
            fluid
            grid-list-lg
    >
        <v-layout row wrap>
        <v-flex v-for="exam in examList" v-bind:key="exam.id" xl12 lg12 md12 sm12 xs12>
            <v-card>
                <v-card-title primary-title>
                    <h3 class="title mb-0">{{exam.title}}</h3>
                </v-card-title>

                    <v-card-text>
                        <h3 class="subheading">الدرجه {{exam.mark}}</h3>
                    </v-card-text>
                <v-card-actions>
                    <v-btn :to="/Exam/+exam.id" class="mb-2" round color="primary" dark>بدء
                        <v-icon dark left>launch</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
    </v-layout>
    </v-container>
</template>

<script>
    export default {
        data() {
            return {
                examList: []

            };
        },

        mounted() {
            console.log('mounted');
            this.initData();
        },
        methods: {
            initData() {
                axios.get('get-exams')
                    .then(({data})=>{
                        this.examList=data;
                       console.log(data)
                    })
                    .catch((resp)=> {
                        console.log(resp);
                    });
            },
        }
    };
</script>

<style scoped>

</style>