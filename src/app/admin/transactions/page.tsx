

import { getSession } from 'app/actions/auth';
import TransactionTable from 'components/admin/dashboards/default/TransactionsTable';
import PageContent from 'components/PageContent';
 
// const fetcher = (url:string) => fetch(url,{ method: 'POST',
//   headers: {
//   'Accept': 'application/json',
//   'Content-Type': 'application/json',
//   'Authorization': 'Bearer ' +  localStorage.getItem('token')
//   },
// // body: raw
// }).then((res) => res.json());


async function getoData(){
  const startdate =  new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10).toString();
  const stopdate =  new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10).toString();
   //const token = localStorage.getItem('token');
  //const raw = JSON.stringify({"startdate":startdate,"stopdate":stopdate,"prefix":"all","statement_type":"all","status":"all"});
  const session = await getSession()
  const  res = await fetch('https://report.tsxbet.net/reports/all/statement', { method: 'POST',
  headers: {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  // 'Authorization': 'Bearer ' +  token
    },
    body: JSON.stringify({ "startdate": startdate, "stopdate": stopdate, "prefix": session.prefix, "statement_type": "all", "status": "all" })
  });
  return res.json(); 
}

export default async function Page() {

  const data = await getoData()
  
  return (
    <PageContent title="รายการฝากถอน">
  
    <div className="flex items-center gap-2" >
      <div style={{ paddingTop: 10 + 'px' }}>
        <div style={{ marginBottom: 20 + 'px'}}   >

        <TransactionTable tableData={data.data} />

        </div>
      </div>
    </div>
    </PageContent>
  );
}
