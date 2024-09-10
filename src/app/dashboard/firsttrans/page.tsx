

import { getFirstTransaction, updateFirstTransaction } from 'app/actions/auth';
import FirstTransaction from 'components/admin/dashboards/default/FirstTransactions';
import PageContent from 'components/PageContent';

 
const addDays = (dateTime:any, count_days = 0)=>{
  return new Date(new Date(dateTime).setDate(dateTime.getDate() + count_days));
}
export default async function Page() {
  const startdate =  new Date(new Date());//.setDate(new Date().getDate())).toJSON().slice(0, 10).toString();
  const stopdate =  new Date(new Date());//.setDate(new Date().getDate())).toJSON().slice(0, 10).toString();

  await updateFirstTransaction()
  const data = await getFirstTransaction(startdate,stopdate)
 
  return (
    <PageContent title="รายการฝาก ครั้งแรก">
  
    <div className="flex items-center gap-2" >
      <div style={{ paddingTop: 10 + 'px' }}>
        <div style={{ marginBottom: 20 + 'px'}}   >

        <FirstTransaction tableData={data} />

        </div>
      </div>
    </div>
    </PageContent>
  );
}
