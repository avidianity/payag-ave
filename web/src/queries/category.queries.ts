import axios from 'axios';
import { route } from '../helpers';
import { CategoryInterface } from '../interfaces/category.interface';

export async function getCategories() {
	const {
		data: { data },
	} = await axios.get<{ data: CategoryInterface[] }>(route('v1.categories.index'));
	return data;
}
