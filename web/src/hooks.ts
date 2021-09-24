import State from '@avidian/state';
import { useEffect, useState } from 'react';
import Tooltip from 'react-tooltip';

export function useRebuildTooltip() {
	useEffect(() => {
		Tooltip.rebuild();
	});

	useEffect(() => {
		const elements = Array.from(document.querySelector('.sc-ezredP.jexnEe')!.querySelectorAll('button'));
		elements.map((element) => {
			element.addEventListener('click', () => {
				setTimeout(() => Tooltip.rebuild(), 250);
			});
		});
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
		setValues(state.getAll());
	};

	return {
		get,
		set,
		listen: state.listen.bind(state),
		unlisten: state.unlisten.bind(state),
		dispatch: state.dispatch.bind(state),
		clear,
	};
}
