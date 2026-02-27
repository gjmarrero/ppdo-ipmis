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

export const getColumns = (onView: (position: Position) => void, onDelete: (position: Position) => void, onEdit: (position: Position) => void): ColumnDef<Position>[] => [
    // {
    //     accessorKey: 'id',
    //     header: ({ column }) =>
    //         h(Button, {
    //             variant: 'ghost',
    //             onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
    //         }, () => ['Id', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
    //     cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('id')),
    //     meta: {
    //         filterable: true,
    //     },
    // },
    {
        accessorKey: 'odsu',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Office/Division', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('odsu')),
        meta: {
            filterable: true
        },
    },
    {
        accessorKey: 'title',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Title', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('title')),
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const position = row.original

            return h('div', { class: 'flex items-center gap-2' }, [
                // h(Button, {
                //     variant: 'outline',
                //     class: 'flex items-center gap-2',
                //     onClick: () => onView(position),
                // }, {
                //     default: () => [
                //         h(Eye, { class: 'w-4 h-4' }),
                //         '',
                //     ],
                // }),

                h(Button, {
                    variant: 'newsecondary',
                    class: 'flex items-center gap-2',
                    onClick: () => onEdit(position),
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
                                        h(AlertDialogDescription, {}, 'This action cannot be undone. It will permanently delete this position.'),
                                    ]
                                }),
                                h(AlertDialogFooter, {}, {
                                    default: () => [
                                        h(AlertDialogCancel, {}, 'Cancel'),
                                        h(AlertDialogAction, {
                                            onClick: () => onDelete(position)
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
                //     onClick: () => onDelete(position),
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
