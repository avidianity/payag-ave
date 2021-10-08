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

	append(name: string, value: any, fileName?: string | undefined) {
		super.append(name, value, fileName);
	}

	set(name: string, value: any, fileName?: string | undefined) {
		super.set(name, value, fileName);
	}
}
