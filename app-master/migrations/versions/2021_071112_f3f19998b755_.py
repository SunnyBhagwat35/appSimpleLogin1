"""empty message

Revision ID: f3f19998b755
Revises: 8e70205a5308
Create Date: 2021-07-11 12:26:31.267912

"""
import sqlalchemy_utils
from alembic import op
import sqlalchemy as sa


# revision identifiers, used by Alembic.
revision = 'f3f19998b755'
down_revision = '8e70205a5308'
branch_labels = None
depends_on = None


def upgrade():
    # ### commands auto generated by Alembic - please adjust! ###
    op.add_column('email_log', sa.Column('alias_id', sa.Integer(), nullable=True))
    op.create_index(op.f('ix_email_log_alias_id'), 'email_log', ['alias_id'], unique=False)
    op.create_foreign_key(None, 'email_log', 'alias', ['alias_id'], ['id'], ondelete='cascade')
    # ### end Alembic commands ###


def downgrade():
    # ### commands auto generated by Alembic - please adjust! ###
    op.drop_constraint(None, 'email_log', type_='foreignkey')
    op.drop_index(op.f('ix_email_log_alias_id'), table_name='email_log')
    op.drop_column('email_log', 'alias_id')
    # ### end Alembic commands ###
