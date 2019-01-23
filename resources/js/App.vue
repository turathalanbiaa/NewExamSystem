<template>
    <v-app id="inspire">
        <v-navigation-drawer
                clipped
                v-model="drawer"
                fixed
                right
                app
        >
            <v-img :aspect-ratio="16/9" src="online-Exam.jpg">
                <!--<v-layout pa-2 column fill-height class="lightbox white&#45;&#45;text">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <div class="subheading">Jonathan Lee</div>
                        <div class="body-1">heyfromjonathan@gmail.com</div>
                    </v-flex>
                </v-layout>-->
            </v-img>

            <v-list dense>
                <v-list-tile to="/">
                    <v-list-tile-action>
                        <v-icon color="green" >home</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>الرئيسيه</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar
                clipped-right
                color="blue darken-3"
                dark
                app
                fixed
        >
            <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <span>نضام الامتحانات</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-avatar size="32px" tile>
                    <img
                            src="logo.png"

                    >
                </v-avatar>

        </v-toolbar>
        <v-content>
            <router-view name="Page1"></router-view>
            <router-view></router-view>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        data: () => ({
            drawer: null,

        }),
        props: {
            source: String
        }
    }
</script>
<style scoped>

</style>