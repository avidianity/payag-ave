import axios from 'axios';
import { route } from '../helpers';
import { UserInterface } from '../interfaces/user.interface';
import { stringify } from 'query-string';

export async function getUsers(params?: Record<string, any>) {
	const {
		data: { data },
	} = await axios.get<{ data: UserInterface[] }>(
		`${route('v1.users.index')}${params ? `?${stringify(params, { arrayFormat: 'bracket' })}` : ''}`
	);
	return data;
}

export async function getUser(id: number | string) {
	const {
		data: { data },
	} = await axios.get<{ data: UserInterface }>(route('v1.users.show', { user: id }));
	return data;
}

export async function createUser(payload: Partial<UserInterface> | FormData) {
	const {
		data: { data },
	} = await axios.post<{ data: UserInterface }>(route('v1.users.store'), payload);
	return data;
}

export async function updateUser(id: number | string, payload: Partial<UserInterface> | FormData) {
	if (payload instanceof FormData) {
		payload.set('_method', 'PUT');
	}

	const {
		data: { data },
	} = await axios.post<{ data: UserInterface }>(
		route('v1.users.update', { user: id }),
		payload instanceof FormData ? payload : { ...payload, _method: 'PUT' }
	);
	return data;
}

export async function deleteUser(id: number | string) {
	await axios.delete(route('v1.users.destroy', { user: id }));
}
