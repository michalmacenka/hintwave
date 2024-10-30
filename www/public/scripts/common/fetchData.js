/**
 * @param {String} method
 * @param {String} url
 * @param {Object} data
 */
export default async function fetchData(method, url, data) {
  try {
    const options = {
      method,
      headers: {
        "Content-Type": "application/json",
      },
      ...(data && { body: JSON.stringify(data) }),
    };

    const response = await fetch(url, options);
    const responseData = await response.json();
    const res = {
      status: response.status,
      body: responseData
    }


    if (!response.ok) {
      throw res;
    }

    return res;
  } catch (err) {
    throw err ?? new Error('Something went wrong');
  }
}