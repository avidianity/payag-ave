import axios from 'axios';
import { route } from '../helpers';
import { CategoryInterface } from '../interfaces/category.interface';

export async function getCategories() {
	const {
		data: { data },
	} = await axios.get<{ data: CategoryInterface[] }>(route('v1.categories.index'));
	return data;
}

export async function getCategory(id: number | string) {
	const {
		data: { data },
	} = await axios.get<{ data: CategoryInterface }>(route('v1.categories.show', { category: id }));
	return data;
}

export async function createCategory(payload: Partial<CategoryInterface> | FormData) {
	const {
		data: { data },
	} = await axios.post<{ data: CategoryInterface }>(route('v1.categories.store'), payload);
	return data;
}

export async function updateCategory(id: number | string, payload: Partial<CategoryInterface> | FormData) {
	const {
		data: { data },
	} = await axios.put<{ data: CategoryInterface }>(route('v1.categories.update', { category: id }), payload);
	return data;
}

export async function deleteCategory(id: number | string) {
	await axios.delete(route('v1.categories.destroy', { category: id }));
}
