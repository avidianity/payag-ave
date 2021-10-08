import axios from 'axios';
import { route } from '../helpers';
import { ProductInterface } from '../interfaces/product.interface';

export async function getProducts() {
	const {
		data: { data },
	} = await axios.get<{ data: ProductInterface[] }>(route('v1.products.index'));
	return data;
}

export async function getProduct(id: number | string) {
	const {
		data: { data },
	} = await axios.get<{ data: ProductInterface }>(route('v1.products.show', { product: id }));
	return data;
}

export async function createProduct(payload: Partial<ProductInterface> | FormData) {
	const {
		data: { data },
	} = await axios.post<{ data: ProductInterface }>(route('v1.products.store'), payload);
	return data;
}

export async function updateProduct(id: number | string, payload: Partial<ProductInterface> | FormData) {
	if (payload instanceof FormData) {
		payload.set('_method', 'PUT');
	}

	const {
		data: { data },
	} = await axios.post<{ data: ProductInterface }>(
		route('v1.products.update', { product: id }),
		payload instanceof FormData ? payload : { ...payload, _method: 'PUT' }
	);
	return data;
}

export async function deleteProduct(id: number | string) {
	await axios.delete(route('v1.products.destroy', { product: id }));
}
