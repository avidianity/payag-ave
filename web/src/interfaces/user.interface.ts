import { FileInterface } from './file.interface';
import { ModelInterface } from './model.interface';

export enum UserRoles {
	ADMIN = 'admin',
	EMPLOYEE = 'employee',
	CUSTOMER = 'customer',
}

export interface UserInterface extends ModelInterface {
	name: string;
	email: string;
	email_verified_at?: string;
	password: string;
	status: boolean;
	role: UserRoles;
	phone: string;
	picture?: FileInterface;
}
