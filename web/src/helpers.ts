import { isArray, isString } from 'lodash';
import toastr from 'toastr';

let networkHandle: number | null = null;
let authHandle: number | null = null;

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
						toastr.error('Authentication has expired. Please try logging in and try again.');
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
