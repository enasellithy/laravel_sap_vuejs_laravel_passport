import Home from './components/Home';
import SingUp from './components/auth/SingUp';
import Login from './components/auth/Login';
import AddNotes from "./components/AddNotes";
import EditNotes from "./components/EditNotes";

export const routes = [
    {path: '/', component: Home, name: 'home'},
    {path: '/singup', component: SingUp, name: 'singup', meta: { requiresAuth: false }},
    {path: '/login', component: Login, name: 'login', meta: { requiresAuth: false }},
    {path: '/addnotes', component: AddNotes, name: 'add_note', meta: { requiresAuth: true }},
    {path: '/notes/:id', component: EditNotes, name: 'edit_notes', meta: { requiresAuth: true }},
];
