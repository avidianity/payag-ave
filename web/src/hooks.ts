import State from '@avidian/state';
import { useEffect, useState } from 'react';
import Tooltip from 'react-tooltip';

export function useRebuildTooltip() {
	const [eventsAttached, setEventsAttached] = useState(false);
	useEffect(() => {
		Tooltip.rebuild();
		const handle = setTimeout(() => Tooltip.rebuild(), 1000);
		return () => {
			clearTimeout(handle);
		};
	});

	useEffect(() => {
		if (!eventsAttached) {
			const handle = setInterval(() => {
				const paginationButton = document.querySelector('.jexnEe');
				if (paginationButton) {
					const elements = Array.from(paginationButton.querySelectorAll('button'));
					elements.forEach((element) => {
						element.addEventListener('click', () => {
							setTimeout(() => Tooltip.rebuild(), 50);
						});
					});
					setEventsAttached(true);
					clearInterval(handle);
				}
			}, 1000);
		}
	}, []);
}

export function useGlobalState() {
	const state = State.getInstance();
	const [values, setValues] = useState(state.getAll());

	const set = (key: string, value: any) => {
		state.set(key, value);
		setValues({
			...values,
			[key]: value,
		});
	};

	function get<T = any>(key: string): T {
		const value = values[key];
		return value;
	}

	const clear = () => {
		state.clear();
		setValues({});
	};

	const has = (key: string) => {
		if (key in values) {
			return true;
		}

		return state.has(key);
	};

	return {
		get,
		set,
		clear,
		has,
		listen: state.listen.bind(state),
		unlisten: state.unlisten.bind(state),
		dispatch: state.dispatch.bind(state),
	};
}
