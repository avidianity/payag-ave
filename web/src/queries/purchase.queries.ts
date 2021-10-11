import axios from 'axios';
import { route } from '../helpers';
import { PurchaseInterface } from '../interfaces/purchase.interface';

export async function getPurchases() {
	const {
		data: { data },
	} = await axios.get<{ data: PurchaseInterface[] }>(route('v1.purchases.index'));
	return data;
}

export async function getPurchase(id: number | string) {
	const {
		data: { data },
	} = await axios.get<{ data: PurchaseInterface }>(route('v1.purchases.show', { purchase: id }));
	return data;
}

export async function createPurchase(payload: Partial<PurchaseInterface> | FormData) {
	const {
		data: { data },
	} = await axios.post<{ data: PurchaseInterface }>(route('v1.purchases.store'), payload);
	return data;
}

export async function updatePurchase(id: number | string, payload: Partial<PurchaseInterface> | FormData) {
	if (payload instanceof FormData) {
		payload.set('_method', 'PUT');
	}

	const {
		data: { data },
	} = await axios.post<{ data: PurchaseInterface }>(
		route('v1.purchases.update', { purchase: id }),
		payload instanceof FormData ? payload : { ...payload, _method: 'PUT' }
	);
	return data;
}

export async function deletePurchase(id: number | string) {
	await axios.delete(route('v1.purchases.destroy', { purchase: id }));
}
