<template>
    <v-container
            fluid
            grid-list-lg
    >
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <h5 class="headline mb-0">السؤال الاول:</h5>
                            <div>ما هي الصلاة التي يركع فيها المصلي أربع مرات ويسجد أربع مرات؟</div>
                        </div>
                    </v-card-title>

                    <v-card-actions>
                        <v-radio-group v-model="row" row>
                            <v-radio label="صلاة الخسوف والكسوف" value="radio-1"></v-radio>
                            <v-radio label="صلاة الليل" value="radio-2"></v-radio>
                        </v-radio-group>
                    </v-card-actions>
                </v-card>
            </v-flex>

            <v-flex xs12>
            <v-card>
                <v-card-title primary-title>
                    <div>
                        <h5 class="headline mb-0">السوال الثاني:</h5>
                        <div> عرف الصوم</div>
                    </div>
                </v-card-title>

                <v-card-actions>
                    <v-textarea
                            name="input-7-1"
                    ></v-textarea>
                </v-card-actions>
            </v-card>
        </v-flex>

            <v-flex xs12>
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <h5 class="headline mb-0">Kangaroo Valley Safari</h5>
                            <div>Located two hours south of Sydney in the Southern Highlands of New South Wales, ...</div>
                        </div>
                    </v-card-title>

                    <v-card-actions>
                        <v-radio-group v-model="row" row>
                            <v-radio label="Option 1" value="radio-1"></v-radio>
                            <v-radio label="Option 2" value="radio-2"></v-radio>
                        </v-radio-group>
                    </v-card-actions>
                </v-card>
            </v-flex>

        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "Page1"
    }
</script>

<style scoped>

</style>