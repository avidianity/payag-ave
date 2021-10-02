import { UserInterface } from '../interfaces/user.interface';

export interface LoginResponse {
	token: string;
	user: UserInterface;
}
