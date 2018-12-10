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

                <v-list-tile to="/">
                    <v-list-tile-action>
                        <v-icon color="light-blue lighten-1">settings</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title >الاعدادت</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-list-tile to="/">
                    <v-list-tile-action>
                        <v-icon color="teal lighten-1">help</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>المساعده</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar
                clipped-right
                color="teal"
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
            <v-container fluid fill-height>
                <v-layout justify-center align-center>

                </v-layout>
            </v-container>
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