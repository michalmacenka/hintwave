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
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const responseData = await response.json();

    return {
      code: response.status,
      data: responseData,
    };
  } catch (err) {
    throw new Error(`Failed to fetch data: ${err.message}`);
  }
}