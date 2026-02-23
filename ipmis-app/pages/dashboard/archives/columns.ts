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

export const getColumns = (onView: (archive: Archive) => void, onDelete: (archive: Archive) => void, onEdit: (archive: Archive) => void): ColumnDef<Archive>[] => [
    {
        accessorKey: 'id',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['id', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('id')),
    },
    {
        accessorKey: 'doc_type',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Document Type', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('doc_type')),
    },
    {
        accessorKey: 'number',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Document Number', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('number')),
    },
    {
        accessorKey: 'description',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Description/Title', h(ArrowUpDown, { class: 'ml-2 h-4 w-4'})]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('description')),
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const archive = row.original

            return h('div', { class: 'flex items-center gap-2' }, [
                h(Button, {
                    variant: 'outline',
                    class: 'flex items-center gap-2',
                    onClick: () => onView(archive),
                }, {
                    default: () => [
                        h(Eye, { class: 'w-4 h-4' }),
                        '',
                    ],
                }),

                // h(Button, {
                //     variant: 'outline',
                //     class: 'flex items-center gap-2',
                //     onClick: () => onEdit(archive),
                // }, {
                //     default: () => [
                //         h(PenLine, { class: 'w-4 h-4' }),
                //         '',
                //     ],
                // }),

                // h(AlertDialog, {}, {
                //     default: () => [
                //         h(AlertDialogTrigger, { asChild: true }, {
                //             default: () => h(Button, {
                //                 variant: 'outline',
                //                 class: 'flex items-center gap-2 text-red-600',
                //             }, { default: () => [h(Trash, { class: 'w-4 h-4' })] })
                //         }),
                //         h(AlertDialogContent, {}, {
                //             default: () => [
                //                 h(AlertDialogHeader, {}, {
                //                     default: () => [
                //                         h(AlertDialogTitle, {}, 'Are you sure?'),
                //                         h(AlertDialogDescription, {}, 'This action cannot be undone. It will permanently delete this file.'),
                //                     ]
                //                 }),
                //                 h(AlertDialogFooter, {}, {
                //                     default: () => [
                //                         h(AlertDialogCancel, {}, 'Cancel'),
                //                         h(AlertDialogAction, {
                //                             onClick: () => onDelete(archive)
                //                         }, { default: () => 'Delete' })
                //                     ]
                //                 })
                //             ]
                //         })
                //     ]
                // }),

                // h(Button, {
                //     variant: 'outline',
                //     class: 'flex items-center gap-2',
                //     onClick: () => onDelete(plantilla),
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
