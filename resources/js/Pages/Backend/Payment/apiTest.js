const fs = require('fs')

const getData = async () => {
    // const res = await fetch('https://jsonplaceholder.typicode.com/todos/1').then(res => res.json())
    // fs.writeFileSync(__dirname+'/op.json', JSON.stringify(res))
    // console.log(res);
    const token = "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiJiODcyZWM3Mi03Yzc5LTQyYmYtYjJjMi1jOTI3YTlhZTRiZWYiLCJzdWIiOiIyODkiLCJpYXQiOjE2OTY5Mjg5NDcsImV4cCI6MjAxMjU0ODE0NywicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.6PjgueSxR00iO-iUpCbHn7BxXnVrJ1Wzm35glaxSNnw"
    // const resp = await fetch(
    //     `https://api.sandbox.pawapay.cloud/deposits`,
    //     {
    //       method: 'POST',
    //       headers: {
    //         'Content-Type': 'application/json',
    //         Authorization: `Bearer ${token}`
    //       },
    //       body: JSON.stringify({
    //         depositId: '3a8875f6-fdc8-4140-b33d-cb94ec272436',
    //         amount: '10',
    //         currency: 'ZMW',
    //         country: 'ZMB',
    //         correspondent: 'MTN_MOMO_ZMB',
    //         payer: {
    //           type: 'MSISDN',
    //           address: {value: '260961234567'}
    //         },
    //         customerTimestamp: '2020-02-21T17:32:28Z',
    //         statementDescription: 'Up to 22 chars note',
    //         preAuthorisationCode: 'QJS3RSKZXY'
    //       })
    //     }
    // ).then(res => res.json());
    // fs.writeFileSync(__dirname+'/op.json', JSON.stringify(resp))
    // console.log(resp);
    fetch('http://127.0.0.1:8000/mobile-pay/final-status-callback', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`
        },
        body: JSON.stringify({
          depositId: '3a8875f6-fdc8-4140-b33d-cb94ec272436',
          amount: '10',
          currency: 'ZMW',
          country: 'ZMB',
          correspondent: 'MTN_MOMO_ZMB',
          payer: {
            type: 'MSISDN',
            address: {value: '260961234567'}
          },
          customerTimestamp: '2020-02-21T17:32:28Z',
          statementDescription: 'Up to 22 chars note',
          preAuthorisationCode: 'QJS3RSKZXY'
        })
    }).then(res => res.json())
    .then(data => {
      console.log(data);
    })
}

getData()



// const data = await resp.json();
// console.log(data);