import Vue from 'vue';
import Router from 'vue-router';
import Home from '../components/Home.vue';
import PlantView from '../components/PlantView.vue';
import Profile from '../components/Profile.vue';

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: '/home',
            name: 'home',
            component: Home
        },
        {
            path: '/profile',
            name: 'profile',
            component: Profile
        },
        {
            path: '/plant/:id?',
            name: 'plant',
            component: PlantView
        }
    ]
});
