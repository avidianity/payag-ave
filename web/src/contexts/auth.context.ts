import { createContext } from 'react';
import { UserInterface } from '../interfaces/user.interface';

export const AuthContext = createContext<{
	user: UserInterface | null;
	setUser: (user: UserInterface | null) => void;
	token: string | null;
	setToken: (token: string | null) => void;
}>({} as any);
