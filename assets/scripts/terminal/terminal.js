import IMask from 'imask';

const opts = {
    mask: [
        { mask: /^\d+$/ },
        {
            mask: 'num',
            lazy: false,
            blocks: {
                num: {
                    mask: Number,
                    scale: 0,
                    thousandsSeparator: ',',
                    padFractionalZeros: true,
                }
            }
        }
    ],
};

const input = document.getElementById('create_amountMask');
const unmasked = document.getElementById('create_amount');

const mask = IMask(input, opts).on('accept', () => {
    input.value = mask.value;
    unmasked.value = mask.unmaskedValue;
});
