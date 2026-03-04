# Eloquent ORM

## Performance Tips

1. **Always eager load relationships** - Avoid N+1 queries
2. **Use chunking for large datasets** - Prevent memory exhaustion
3. **Index foreign keys** - Speed up joins
4. **Use select() to limit columns** - Reduce data transfer
5. **Cache expensive queries** - Use Redis/Memcached
6. **Use database indexing** - Add indexes in migrations
7. **Avoid using model events for heavy operations** - Use queues instead
8. **Use lazy collections** - For processing large datasets
