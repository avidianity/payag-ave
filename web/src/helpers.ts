import { isArray, isString } from 'lodash';
import toastr from 'toastr';
import { UserInterface, UserRoles } from './interfaces/user.interface';
import routes from './routes.json';
import { routes as mainRoutes } from './routes';

let networkHandle: number | null = null;
let authHandle: number | null = null;

export function getUserMainPage(user: UserInterface) {
	console.log(user);
	return user.role === UserRoles.CUSTOMER ? mainRoutes.HOME : mainRoutes.DASHBOARD;
}

export function formatNumber(number: number) {
	return Number.isInteger(number) ? number : number.toFixed(2);
}

export function handleError(error: any, useHandle = true) {
	if (error) {
		if (error.response) {
			const response = error.response;
			if (response.data.errors && response.status === 422) {
				return Object.values<string[]>(response.data.errors)
					.reverse()
					.forEach((errors) => errors.map((error) => toastr.error(error)));
			}
			if (response.data.message) {
				if ([500, 400, 403, 404].includes(response.status)) {
					return toastr.error(response.data.message);
				}

				if (response.status === 401) {
					if (authHandle === null) {
						if (response.data.message && !response.data.message.includes('Unauthenticated')) {
							toastr.error(response.data.message);
						} else {
							toastr.error('Authentication has expired. Please try logging in and try again.');
						}
						authHandle = setTimeout(() => {
							if (authHandle !== null) {
								clearTimeout(authHandle);
								authHandle = null;
							}
						}, 5000);
					}
					return;
				}

				if (isArray(response.data.message)) {
					return response.data.message.forEach((message: string) => toastr.error(message, undefined, { extendedTimeOut: 2000 }));
				} else if (isString(response.data.message)) {
					return toastr.error(response.data.message);
				}
			}
		} else if (error.message) {
			if (error.message.includes('Network Error')) {
				if (networkHandle === null && useHandle) {
					toastr.error('Unable to connect. Please check your internet connection or the server may be down.');
					networkHandle = setTimeout(() => {
						if (networkHandle !== null) {
							clearTimeout(networkHandle);
							networkHandle = null;
						}
					}, 5000);
					return;
				}
			} else {
				return toastr.error(error.message);
			}
		}
	} else {
		return toastr.error('Something went wrong, please try again later.', 'Oops!');
	}
}

export function urlWithToken(url: string, token: string) {
	const uri = new URL(url);

	uri.searchParams.set('token', token);

	return uri.toString();
}

export function route(name: keyof typeof routes, parameters?: Record<string, any>) {
	if (!(name in routes)) {
		throw new Error(`${name} does not exist in routes file.`);
	}

	const route = routes[name];

	if (parameters) {
		let url = route.uri;
		for (const key in parameters) {
			const value = parameters[key];
			url = url.replace(`{${key}}`, value.toString());
		}

		return `/${url}`;
	}

	return `/${route.uri}`;
}
