import React, { useEffect } from 'react';
import { useTable, usePagination, useGlobalFilter,useSortBy } from 'react-table';
import axios from 'axios'; // 假設您使用 axios 來處理 AJAX 請求

function CrawlDataTable({datatable_url}) {
    const columns = React.useMemo(
        () => [
            {
                Header: 'action',
                accessor:'action',
                Cell: ({ value }) => ( // 使用 Cell 属性来渲染自定义内容
                    <div dangerouslySetInnerHTML={{ __html: value }} />
                ),
                sortable: false,
            },
            {
                Header: 'id',
                accessor:'id',
            },
            {
                Header: 'title',
                accessor:'title',
            },
            {
                Header: 'url',
                accessor:'url',
            },
            {
                Header: 'description',
                accessor:'description',
            },
            {
                Header: 'created_at',
                accessor:'created_at',
            },
        ],
        []
    );

    const [data, setData] = React.useState([]); // 存放從伺服器獲取的數據

    useEffect(() => {
        // 在組件加載時，發送 AJAX 請求獲取數據
        axios.get(datatable_url) // 替換為您的伺服器端數據 URL
            .then((response) => {
                setData(response.data.data); // 更新數據狀態
            })
            .catch((error) => {
                console.error('發生錯誤：', error);
            });
    }, []);

    const {
        getTableProps,
        getTableBodyProps,
        headerGroups,
        page, // 這是分頁後的數據
        prepareRow,
        state,
        setGlobalFilter,
        canPreviousPage, // 確保這些方法和屬性被添加
        canNextPage,
        pageOptions,
        previousPage,
        nextPage,
        setPageSize,
    } = useTable(
        {
            columns,
            data,
            initialState: {
                // 初始排序狀態
                sortBy: [{ id: 'id' /* 初始排序的列ID */, desc: false /* true 表示降序 */ }],
            },
        },
        useGlobalFilter,
        useSortBy,
        usePagination // 使用分頁插件
    );

    const { globalFilter, pageIndex, pageSize,sortBy  } = state;
    return (

        <div className="py-12">

            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div className="mb-4">
                    <select
                        value={pageSize}
                        onChange={(e) => {
                            setPageSize(Number(e.target.value));
                        }}
                    >
                        {[10, 20, 30, 40, 50].map((pageSize) => (
                            <option key={pageSize} value={pageSize}>
                                顯示 {pageSize} 筆
                            </option>
                        ))}
                    </select>
                    <span className="spacer"></span>
                    {/* 全局搜尋輸入框 */}
                    <input
                        type="text"
                        className="mb-2 p-2 border rounded"
                        value={globalFilter || ''}
                        onChange={(e) => setGlobalFilter(e.target.value)}
                        placeholder="全局搜尋..."
                    />
                </div>
                <table
                    {...getTableProps()}
                    className="crawl_datatable"
                    id="crawl_datatable"
                    style={{ borderCollapse: 'collapse', borderSpacing: 0, width: '100%' }}
                >
                    <thead>
                    {headerGroups.map((headerGroup) => (
                        <tr {...headerGroup.getHeaderGroupProps()}>
                            {headerGroup.headers.map((column) => (
                                <th
                                    {...column.getHeaderProps(column.getSortByToggleProps())} // 添加排序相關屬性
                                >
                                    {column.render('Header')}
                                    <span>
                                        {column.isSorted ? (column.isSortedDesc ? ' 🔽' : ' 🔼') : ''}
                                    </span>
                                </th>
                            ))}
                        </tr>
                    ))}
                    </thead>
                    <tbody {...getTableBodyProps()}>
                    {page.map((row) => {
                        prepareRow(row);
                        return (
                            <tr {...row.getRowProps()}>
                                {row.cells.map((cell) => {
                                    return <td {...cell.getCellProps()}>{cell.render('Cell')}</td>;
                                })}
                            </tr>
                        );
                    })}
                    </tbody>
                </table>
                {/* 分頁控制按鈕 */}
                <div className="mt-4">
                    <button className="button" onClick={() => previousPage()} disabled={!canPreviousPage}>
                        previous
                    </button>
                    <span className="spacer">
                        {'    '}
                        <strong>
                          {pageIndex + 1} / {pageOptions.length}
                        </strong>
                        {'    '}
                    </span>
                    <button className="button" onClick={() => nextPage()} disabled={!canNextPage}>
                        next
                    </button>
                </div>

            </div>
        </div>
    );
}

export default CrawlDataTable;
