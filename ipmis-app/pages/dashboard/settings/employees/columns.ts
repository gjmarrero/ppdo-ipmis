// columns.ts
import type { ColumnDef } from '@tanstack/vue-table'
import { ArrowUpDown, Eye, PenLine, Trash } from 'lucide-vue-next'
import { h } from 'vue'
import Button from '~/components/ui/button/Button.vue'

import {
    AlertDialog,
    AlertDialogTrigger,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogCancel,
    AlertDialogAction,
} from '@/components/ui/alert-dialog'

export const getColumns = (onView: (employee: Employee) => void, onDelete: (employee: Employee) => void, onEdit: (employee: Employee) => void): ColumnDef<Employee>[] => [
    // {
    //     accessorKey: 'id',
    //     header: ({ column }) =>
    //         h(Button, {
    //             variant: 'ghost',
    //             onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
    //         }, () => ['Id', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
    //     cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('id')),
    // },
    {
        accessorKey: 'last_name',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Last Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('last_name')),
        meta: {
            filterable: true,
        },
    },
    {
        accessorKey: 'first_name',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['First Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('first_name')),
        meta: {
            filterable: true,
        },
    },
    {
        accessorKey: 'middle_name',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Middle Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('middle_name')),
        meta: {
            filterable: true,
        }
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const employee = row.original

            return h('div', { class: 'flex items-center gap-2' }, [
                // h(Button, {
                //     variant: 'outline',
                //     class: 'flex items-center gap-2',
                //     onClick: () => onView(employee),
                // }, {
                //     default: () => [
                //         h(Eye, { class: 'w-4 h-4' }),
                //         '',
                //     ],
                // }),

                h(Button, {
                    variant: 'newsecondary',
                    class: 'flex items-center gap-2',
                    onClick: () => onEdit(employee),
                }, {
                    default: () => [
                        h(PenLine, { class: 'w-4 h-4' }),
                        '',
                    ],
                }),

                h(AlertDialog, {}, {
                    default: () => [
                        h(AlertDialogTrigger, { asChild: true }, {
                            default: () => h(Button, {
                                variant: 'newsecondary',
                                class: 'flex items-center gap-2 text-red-600',
                            }, { default: () => [h(Trash, { class: 'w-4 h-4' })] })
                        }),
                        h(AlertDialogContent, {}, {
                            default: () => [
                                h(AlertDialogHeader, {}, {
                                    default: () => [
                                        h(AlertDialogTitle, {}, 'Are you sure?'),
                                        h(AlertDialogDescription, {}, 'This action cannot be undone. It will permanently delete this employee.'),
                                    ]
                                }),
                                h(AlertDialogFooter, {}, {
                                    default: () => [
                                        h(AlertDialogCancel, {}, 'Cancel'),
                                        h(AlertDialogAction, {
                                            onClick: () => onDelete(employee)
                                        }, { default: () => 'Delete' })
                                    ]
                                })
                            ]
                        })
                    ]
                }),

                // h(Button, {
                //     variant: 'outline',
                //     class: 'flex items-center gap-2',
                //     onClick: () => onDelete(employee),
                // }, {
                //     default: () => [
                //         h(Trash, { class: 'w-4 h-4' }),
                //         '',
                //     ],
                // }),
            ])
        },
    },
]
