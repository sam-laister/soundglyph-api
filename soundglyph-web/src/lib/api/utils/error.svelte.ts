export interface ApiError {
    title: string;
    description: string;
    // debugTokenLink: string;
    // trace: Trace[];
}

export interface Trace {
    namespace: string;
    short_class: string;
    class: string;
    type: string;
    function: string;
    file: string;
    line: number;
    args: string[][];
}

export class ApiErrorModal {
    showErrorModal = $state(false);
    apiErrors: ApiError[] = $state([]);
}

export const apiErrors = new ApiErrorModal();