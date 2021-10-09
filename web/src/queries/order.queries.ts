import axios from 'axios';
import { route } from '../helpers';
import { OrderInterface } from '../interfaces/order.interface';

export async function getOrders() {
	const {
		data: { data },
	} = await axios.get<{ data: OrderInterface[] }>(route('v1.orders.index'));
	return data;
}

export async function getOrder(id: number | string) {
	const {
		data: { data },
	} = await axios.get<{ data: OrderInterface }>(route('v1.orders.show', { order: id }));
	return data;
}

export async function createOrder(payload: Partial<OrderInterface> | FormData) {
	const {
		data: { data },
	} = await axios.post<{ data: OrderInterface }>(route('v1.orders.store'), payload);
	return data;
}

export async function updateOrder(id: number | string, payload: Partial<OrderInterface> | FormData) {
	if (payload instanceof FormData) {
		payload.set('_method', 'PUT');
	}

	const {
		data: { data },
	} = await axios.post<{ data: OrderInterface }>(
		route('v1.orders.update', { order: id }),
		payload instanceof FormData ? payload : { ...payload, _method: 'PUT' }
	);
	return data;
}

export async function deleteOrder(id: number | string) {
	await axios.delete(route('v1.orders.destroy', { order: id }));
}
