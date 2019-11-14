<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <router-link class="navbar-brand" to="/">Navbar</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                </ul>
                <ul class="navbar-nav pull-right">
                    <li class="nav-item" v-if="!authenticated && !user">
                        <router-link to="/singup">SingUp</router-link>
                    </li>
                    <li class="nav-item" v-if="!authenticated && !user">
                        <router-link to="/login">Login</router-link>

                    </li>
                    <li class="nav-item" v-if="authenticated && user">
                        <a href="#" class="" @click.stop="logout">Sign Out</a>
                    </li>
                </ul>
            </div>
        </nav>
        <router-view/>
    </div>
</template>

<script>
    export default {
        name: "App.vue",
        data() {
            return {
                authenticated: auth.check(),
                user: auth.user,
            }
        },
        mounted() {
            Event.$on('userLoggedIn', () => {
                this.authenticated = true;
                this.user = auth.user;
            });
        },
        methods: {
            logout() {
                axios.post('/api/logout'+auth.token).then((data)=>{
                    auth.logout();
                    localStorage.removeItem('token');
                    localStorage.removeItem('expiresIn');
                    auth.logout();
                    if ( data.status == 200 ) {
                        this.$router.push({name: 'login'});
                        location.reload();
                        toast.fire({
                            type: 'success',
                            title: 'Sing out in successfully'
                        })
                    }
                }).catch(({response}) => {
                    if (response.status === 401) {
                        console.log(response.status);
                        toast.fire({
                            type: 'danger',
                            title: 'Oops! something wrong.'
                        })
                    }
                });
            }
        }
    }
</script>

<style>
    .navbar {
        background-color: #000 !important;
    }
    .navbar-light .navbar-brand {
        font-weight: 600;
    }
    .navbar-light .navbar-brand,
    .navbar-light .navbar-brand:hover,
    .navbar-light .navbar-brand:active,
    .navbar-light .navbar-brand:visited {
        color: rgba(247, 21, 125, 0.9);
        font-size: 1.75em;
    }
    .navbar-light .navbar-nav li {
        margin-right: 20px;
    }
    .navbar-light .navbar-nav li a {
        color: rgb(253, 215, 225);
        text-decoration: none;
    }
    .navbar-light li.dropdown #navbarDropdown {
        color: rgb(221, 71, 112);
    }
    .navbar-light .navbar-toggler {
        color: rgb(178, 61, 110);
        border-color: rgb(221, 71, 112);
        background-color: rgb(253, 215, 225);
    }
    .navbar-light .navbar-toggler span {

    }
</style>
