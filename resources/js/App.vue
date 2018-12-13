<template>
    <v-app id="inspire">
        <v-navigation-drawer
                clipped
                v-model="drawer"
                fixed
                right
                app
        >
            <v-list dense>
                <v-list-tile to="/">
                    <v-list-tile-action>
                        <v-icon color="green" >home</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>الرئيسيه</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-list-tile :to="{ name: 'Page2'}">
                    <v-list-tile-action>
                        <v-icon color="light-blue lighten-1">navigate_before</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title >الامتحانات الحاليه</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-list-tile >
                    <v-list-tile-action>
                        <v-icon color="teal lighten-1">navigate_next</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>الامتحانات القادمه</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar
                clipped-right
                color="#6f42c1"
                dark
                app
                fixed
        >
            <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <span class="hidden-sm-and-down">نضام الامتحانات</span>
            </v-toolbar-title>
           <!-- <v-text-field
                    flat
                    solo-inverted
                    hide-details
                    prepend-inner-icon="search"
                    label="Search"
                    class="hidden-sm-and-down"
            ></v-text-field>-->
            <v-spacer></v-spacer>
            <v-btn icon>
                <v-icon>apps</v-icon>
            </v-btn>
            <v-btn icon>
                <v-icon>notifications</v-icon>
            </v-btn>
            <v-btn icon large>
                <v-avatar size="32px" tile>
                    <img
                            src="https://cdn.vuetifyjs.com/images/logos/logo.svg"
                            alt="Vuetify"
                    >
                </v-avatar>
            </v-btn>
        </v-toolbar>
        <v-content>
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