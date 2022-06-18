export default function convertToUpperUnit(quantity, unit){
    let relation = unit.relation.split('/')
    let labels = unit.labels.split('/')
    let last = labels.length - 1;
    let recordWithCleanUnit = [];

    for (let i = last; i >= 0; i--) {
        let divisor = parseFloat(relation[i])
        let remainder = (quantity % divisor);
        quantity = (quantity - remainder) / divisor;

        if(i === 0) {
            if (quantity > 0){
                recordWithCleanUnit.push(quantity + ' ' + labels[i]);
            }
        } else {
            remainder = Number.parseFloat(remainder).toFixed(2);
            if (remainder != 0 ) {
                recordWithCleanUnit.push(remainder + ' ' + labels[i]);
            }
        }
    }
    let reverseUnit = recordWithCleanUnit.reverse();
    return reverseUnit.join(' ');
}
