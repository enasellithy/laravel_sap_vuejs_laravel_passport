import axios from 'axios';
import header from "vue-resource/src/http/interceptor/header";
class Auth {
    constructor() {
        this.token = window.localStorage.getItem('token');
        let userData = window.localStorage.getItem('user');
        this.user = userData ? JSON.parse(userData) : null;
        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
            this.getUser();
        } else {
            this.token = null;
            this.user = null;
            //this.logout();
        }
    }

    login (token, user) {
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        this.token = token;
        this.user = user;
        Event.$emit('userLoggedIn');
    }

    check () {
        return !! this.token;
    }

    getUser() {
        let u = window.localStorage.getItem('user');
        //console.log(this.token);
        axios.get('/api/user'+this.token).then((data)=>{
           // console.log('Status Code Is : ' + data.status);
            //console.log(this.user);
            this.user = data['user'];
            // this.user = data;
        }).catch(({response}) => {
            if (response.status === 401) {
                //console.log(response.status);
                this.logout();
            }
        });
    }

    registerUser (token, user) {
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        this.token = token;
        this.user = user;
        Event.$emit('userSingUp');
    }

    logout (state) {
        localStorage.removeItem('refreshToken');
        localStorage.removeItem('expiresIn');
        // const newState = loadState();
        // Object.keys(newState).forEach(key => {
        //     state[key] = newState[key];
        // });
        // TODO: reinit user state
    }
}
export default new Auth();
