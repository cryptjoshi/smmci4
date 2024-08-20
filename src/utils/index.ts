
export function convertDate(isoDateTime:string){
    //  const isoDateTime = '2024-07-10T14:13:17.000Z';
    const date = new Date(isoDateTime);
    const formattedDateTime = `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}:${date.getSeconds().toString().padStart(2, '0')}`;
    return formattedDateTime;
    
    }