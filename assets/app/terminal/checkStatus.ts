const input = document.getElementById('payment') as HTMLDivElement;

const currentState: string = input.dataset['state'];
const paymentId: string = input.dataset['id'];

type PaymentStatus = {
    id: string;
    status: string;
}

const request = <TResponse>(url: string, config: RequestInit = {}): Promise<TResponse> => {
    return fetch(url, config)
        .then((response) => response.json())
        .then(data => data as TResponse);
}

const observe = async () => {
    const response = await request<PaymentStatus>(`/${paymentId}/json`);

    if (response.status !== currentState) {
        window.location.reload();

        return;
    }

    await observe();
}

(async () => await observe())();
