<template>
    <v-container  fluid
                  grid-list-lg
    >
        <p class="text-lg-right headline"> امتحان اسئلة دينيه عامه الشهر الاول <div>Id {{ $route.params.id }}</div></p>
        <v-layout row wrap>

            <v-flex xs12 sm12 md12 lg12>
                <v-card>
                    <v-card-title primary-title>
                        <h3 class="title mb-0">من أول ملك فرعوني آمن بالتوحيد ؟</h3>
                    </v-card-title>
                    <v-card-text>
                    <v-radio-group column>
                        <v-radio  label="اخناتون" value="اخناتون" @change="getAnswer($event, 1)"></v-radio>
                        <v-radio label="توت عنغ امون" value="توت عنغ امون" @change="getAnswer($event, 1)"></v-radio>
                        <v-radio label="خوفو" value="خوفو" @change="getAnswer($event, 1)"></v-radio>
                        <v-radio label="خفرع" value="خفرع" @change="getAnswer($event, 1)"></v-radio>
                    </v-radio-group>
                    </v-card-text>
                        <v-card-actions>
                            <v-btn class="mb-2" round color="success" dark @click="saveAnswer(1)">حفظ
                                <v-icon dark left>save</v-icon>
                            </v-btn>
                        </v-card-actions>
                </v-card>
            </v-flex>

            <v-flex xs12 sm12 md12 lg12>
                <v-card>
                    <v-card-title primary-title>
                        <h3 class="title mb-0">أول قاضي في البصرة ابو مريم الحنفي؟</h3>
                    </v-card-title>
                    <v-card-text>
                    <v-radio-group column>
                        <v-radio  label="صح" value="صح" @change="getAnswer($event, 2)" v-model="namesThatRhyme"></v-radio>
                        <v-radio label="خطأ" value="خطأ" @change="getAnswer($event, 2)"></v-radio>
                    </v-radio-group>
                        </v-card-text>
                    <v-card-actions>
                        <v-btn class="mb-2" round color="success" dark @click="saveAnswer(2)">حفظ
                            <v-icon dark left>save</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>


            <v-flex xs12 sm12 md12 lg12>
                <v-card>
                    <v-card-title primary-title>
                        <h3 class="title mb-0">أول جبل وضع في الأرض ؟</h3>
                    </v-card-title>
                    <v-card-text>
                        <v-text-field single-line outline @input="getAnswer($event, 3)"
                                      value="Answer"
                        ></v-text-field>
                        </v-card-text>
                    <v-card-actions>
                        <v-btn class="mb-2" round color="success" dark @click="saveAnswer(3)">حفظ
                            <v-icon dark left>save</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>

            <v-flex xs12 sm12 md12 lg12>
                <v-card>
                    <v-card-title primary-title>
                        <h3 class="title mb-0">اشرح العمره</h3>
                    </v-card-title>
                    <v-card-text>
                    <v-textarea outline  @input="getAnswer($event, 4)"
                                value="Answer"
                    ></v-textarea>
                        </v-card-text>
                    <v-card-actions>
                        <v-btn class="mb-2" round color="success" dark @click="saveAnswer(4)">حفظ
                            <v-icon dark left>save</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>

            <v-snackbar
                    v-model="snackbar"
                    :color="color"
                    bottom
                    :timeout=6000>
                {{ snackbarText }}
            </v-snackbar>
            <div class="text-xs-center">
                <v-dialog
                        v-model="dialog"
                        persistent max-width="290"
                >
                    <v-btn
                            slot="activator"
                            color="red"
                            round
                            dark
                    >
                        أنهاء الأمتحان
                    </v-btn>

                    <v-card>
                        <v-card-title
                                class="title red  white--text"
                                primary-title
                        >
                            العقائد
                        </v-card-title>
                        <v-card-text>
                            هل انت متأكد
                        </v-card-text>
                        <v-card-actions>


                            <v-btn
                                    color="info darken-1"
                                    flat
                                    @click="dialog = false"
                            >
                                نعم
                            </v-btn>

                            <v-btn
                                    color="info darken-1"
                                    flat
                                    @click="dialog = false"
                            >
                                لا
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "Page1",
        data() {
            return {
                page: 1,
                dialog:false,
                namesThatRhyme: [],
                snackbar: false,
                snackbarText: '',
                color:'',
                questionId:null,
                answer:null,
            };
        },

        mounted() {
            console.log(this.items);
        },
        methods: {
           saveAnswer(id) {
               if (id !== this.questionId) {
                   this.color='error';
                   this.snackbarText='يجب الاجابة على السوال او تغيير الاجابة';
                   this.snackbar=true;
                   return;
               }
               else{
               console.log("id "+this.questionId);
                console.log("answer "+this.answer);
               this.color='success';
               this.snackbarText='تم الحفظ بنجاح';
               this.snackbar=true;
               this.questionId=null;
               this.answer=null;
               }
            },
            getAnswer(e,id) {
                console.log("Id ",id);
                console.log("Value ",e);

                this.questionId=id;
                this.answer=e;
            },
            onPageChange() {
                console.log('page clicked')
            },
            initData() {
                axios.get('api/map', {params: {lang:this.$i18n.locale}})
                    .then(({data})=>{
                        this.markers=data.data;
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