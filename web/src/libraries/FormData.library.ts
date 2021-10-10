export class FormData extends window.FormData {
	constructor(data?: HTMLFormElement | Record<string, any>) {
		if (data) {
			if (data instanceof HTMLFormElement) {
				super(data);
			} else {
				super();
				for (const key in data) {
					const value = data[key];
					super.set(key, value);
				}
			}
		} else {
			super();
		}
	}
}
