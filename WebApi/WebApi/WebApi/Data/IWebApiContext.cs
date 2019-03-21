using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.ChangeTracking;
using WebApi.Models;

namespace WebApi.Data
{
    public interface IWebApiContext
    {
        DbSet<Customer> Customers { get; set; }

        DbSet<TEntity> Set<TEntity>() where TEntity : class;
        EntityEntry<TEntity> Add<TEntity>(TEntity entity) where TEntity : class;
        EntityEntry Update(object entity);
        EntityEntry Remove(object entity);
        void RemoveRange(params object[] entities);
        TEntity Find<TEntity>(params object[] keyValues) where TEntity : class;
        int SaveChanges();
        EntityEntry<TEntity> Entry<TEntity>(TEntity entity) where TEntity : class;
    }
}
