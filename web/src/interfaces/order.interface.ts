import { ModelInterface } from './model.interface';
import { ProductInterface } from './product.interface';
import { UserInterface } from './user.interface';

export enum OrderStatuses {
	PAID = 'paid',
	UNPAID = 'unpaid',
	DEBT = 'debt',
}

export interface OrderInterface extends ModelInterface {
	customer_id: number;
	biller_id?: number;
	paid: number;
	status: OrderStatuses;
	customer?: UserInterface;
	biller?: UserInterface;
	products?: ProductInterface[];
}
