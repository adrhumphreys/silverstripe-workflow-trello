export const sendSelectedStep = ({
  route,
  stepId,
  recordId,
  recordType,
  setTrelloUrl,
}) => {
  fetch(route, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      stepId,
      recordId,
      recordType,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.error) {
        alert(data.error);
      }

      setTrelloUrl(data.trelloUrl);
    });
};
