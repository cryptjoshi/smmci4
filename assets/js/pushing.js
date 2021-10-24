    
    Pusher.logToConsole = true;
    const pusher = new Pusher(
      `"${process.env.PUSHER_KEY}"`, // Replace with 'key' from dashboard
      {
        cluster:`"${process.env.PUSHER_CLUSTER}"`, // Replace with 'cluster' from dashboard
        forceTLS: true,
      }
    );
    const channel = pusher.subscribe("my-channel");
    channel.bind("my-event", (data) => {
       alert(data)
    });