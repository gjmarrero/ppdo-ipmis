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

export const getColumns = (onView: (plantilla: ScopeOfWork) => void, onDelete: (plantilla: ScopeOfWork) => void, onEdit: (plantilla: ScopeOfWork) => void): ColumnDef<ScopeOfWork>[] => [
    // {
    //     accessorKey: 'id',
    //     header: ({ column }) =>
    //         h(Button, {
    //             variant: 'ghost',
    //             onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
    //         }, () => ['id', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
    //     cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('id')),
    // },
    {
        accessorKey: 'scope',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Scope of Work', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('scope')),
        meta: {
            filterable: true,
        },
    },    
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const scope_of_work = row.original

            return h('div', { class: 'flex items-center gap-2' }, [
                h(Button, {
                    variant: 'newsecondary',
                    class: 'flex items-center gap-2',
                    onClick: () => onEdit(scope_of_work),
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
                                        h(AlertDialogDescription, {}, 'This action cannot be undone. It will permanently delete this scope of work.'),
                                    ]
                                }),
                                h(AlertDialogFooter, {}, {
                                    default: () => [
                                        h(AlertDialogCancel, {}, 'Cancel'),
                                        h(AlertDialogAction, {
                                            onClick: () => onDelete(scope_of_work)
                                        }, { default: () => 'Delete' })
                                    ]
                                })
                            ]
                        })
                    ]
                }),
            ])
        },
    },
]
