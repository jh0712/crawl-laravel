import React, { useState } from 'react';

function UrlInput() {
    const [url, setUrl] = useState('');

    const handleInputChange = (e) => {
        setUrl(e.target.value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        // 在這裡處理提交URL的邏輯，可以向後端發送請求
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    placeholder="輸入URL"
                    value={url}
                    onChange={handleInputChange}
                />
                <button type="submit">提交</button>
            </form>
        </div>
    );
}

export default UrlInput;
