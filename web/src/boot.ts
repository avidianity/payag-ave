import axios from 'axios';
import '@avidian/extras';
import State from '@avidian/state';
import toastr from 'toastr';

window.toastr = toastr;

axios.defaults.baseURL = import.meta.env.VITE_SERVER_URL;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.withCredentials = true;

const state = State.getInstance();

axios.interceptors.request.use((config) => {
	const token = state.get('token');
	if (token) {
		config.headers.common['Authorization'] = `Bearer ${token}`;
	}
	return config;
});
