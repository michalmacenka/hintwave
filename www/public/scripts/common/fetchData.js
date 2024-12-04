/**
 * Makes an HTTP request to the specified URL with the given method and data
 * 
 * @param {String} method - The HTTP method to use (GET, POST, PUT, DELETE, etc)
 * @param {String} url - The URL to send the request to
 * @param {Object} [data] - Optional data to send in the request body
 * @returns {Promise<Object>} Response object containing status code and parsed JSON body
 * @throws {Object} Response object if server returns error status code
 * @throws {Error} Generic error if request fails
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